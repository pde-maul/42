/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   vertical.c                                         :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/05/26 15:11:03 by pde-maul          #+#    #+#             */
/*   Updated: 2017/05/29 14:23:39 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../includes/wolf3d.h"

float			get_vertical_dist(t_env *e, float ray_angle)
{
	t_point		*inc;
	t_point		*point;
	float		dist;

	if (!(inc = (t_point*)malloc(sizeof(t_point))))
		clean_exit(e);
	point = get_first_vertical_point(e, ray_angle);
	inc->x = (is_right_part(ray_angle)) ? +e->cube : -e->cube;
	inc->y = -inc->x * tan(ray_angle * M_PI / 180);
	while (inside_map(point, e) && !is_wall(point, e))
	{
		free(point);
		point = get_next_vertical_point(e, point, inc);
	}
	free(inc);
	dist = get_dist(point, e);
	free(point);
	return (dist);
}

t_point			*get_first_vertical_point(t_env *e, float ray_angle)
{
	t_point		*point;

	if (!(point = (t_point*)malloc(sizeof(t_point))))
		clean_exit(e);
	if (is_right_part(ray_angle))
		point->x = floor(e->pos->x / e->cube) * e->cube + e->cube;
	else
		point->x = floor(e->pos->x / e->cube) * e->cube - 0.001;
	point->y = e->pos->y + (e->pos->x - point->x) * tan(ray_angle * M_PI / 180);
	return (point);
}

t_point			*get_next_vertical_point(t_env *e, t_point *point, t_point *inc)
{
	t_point *next_point;
	float	dist;

	if (!(next_point = (t_point*)malloc(sizeof(t_point))))
		clean_exit(e);
	next_point->x = point->x + inc->x;
	next_point->y = point->y + inc->y;
	dist = get_dist(next_point, e);
	return (next_point);
}
