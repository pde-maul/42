/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   is_it.c                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/05/26 13:39:12 by pde-maul          #+#    #+#             */
/*   Updated: 2017/05/29 12:22:45 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../includes/wolf3d.h"

int			is_up_part(float angle)
{
	if (angle > 0 && angle <= 180)
		return (1);
	else
		return (0);
}

int			inside_map(t_point *cross, t_env *e)
{
	int		i;
	int		j;

	i = cross->y / e->cube;
	j = cross->x / e->cube;
	if (i < e->nb_line && cross->y >= 0 && i >= 0
		&& j < e->nb_col && cross->x >= 0 && j >= 0)
		return (1);
	else
		return (0);
}

int			is_wall(t_point *cross, t_env *e)
{
	int		i;
	int		j;

	i = cross->y / e->cube;
	j = cross->x / e->cube;
	if (e->grid[i][j] == 1)
		return (1);
	else
		return (0);
}

float		get_dist(t_point *wall, t_env *e)
{
	float	pow1;
	float	pow2;
	float	sqrt1;

	pow1 = pow(e->pos->x - wall->x, 2);
	pow2 = pow(e->pos->y - wall->y, 2);
	sqrt1 = sqrt(pow1 + pow2);
	return (sqrt1);
}

int			is_right_part(float angle)
{
	if ((angle >= 0 && angle < 90) || (angle < 360 && angle >= 270))
		return (1);
	else
		return (0);
}
