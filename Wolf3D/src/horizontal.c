/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   horizontal.c                                       :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/05/26 13:15:18 by pde-maul          #+#    #+#             */
/*   Updated: 2017/05/29 14:28:44 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../includes/wolf3d.h"

float		get_horizontal_dist(t_env *e, float ray_angle)
{
	t_point	*inc;
	t_point	*point;
	float	dist;

	if (!(inc = (t_point*)malloc(sizeof(t_point))))
		clean_exit(e);
	point = get_first_horizontal_point(e, ray_angle);
	if (is_up_part(ray_angle))
		inc->y = -e->cube;
	else
		inc->y = +e->cube;
	inc->x = -inc->y / tan(ray_angle * M_PI / 180);
	while (inside_map(point, e) && !is_wall(point, e))
	{
		free(point);
		point = get_next_horizontal_point(e, point, inc);
	}
	free(inc);
	dist = get_dist(point, e);
	free(point);
	return (dist);
}

t_point		*get_first_horizontal_point(t_env *e, float ray_angle)
{
	t_point	*point;

	if (!(point = (t_point*)malloc(sizeof(t_point))))
		clean_exit(e);
	if (is_up_part(ray_angle))
		point->y = floor(e->pos->y / e->cube) * e->cube - 0.001;
	else
		point->y = floor(e->pos->y / e->cube) * e->cube + e->cube;
	point->x = e->pos->x + (e->pos->y - point->y) / tan(ray_angle * M_PI / 180);
	return (point);
}

t_point		*get_next_horizontal_point(t_env *e, t_point *point, t_point *inc)
{
	t_point	*next_point;
	float	dist;

	if (!(next_point = (t_point*)malloc(sizeof(t_point))))
		clean_exit(e);
	next_point->x = point->x + inc->x;
	next_point->y = point->y + inc->y;
	dist = get_dist(next_point, e);
	return (next_point);
}
