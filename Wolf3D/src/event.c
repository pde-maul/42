/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   event.c                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/05/24 15:24:58 by pde-maul          #+#    #+#             */
/*   Updated: 2017/05/29 14:16:48 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../includes/wolf3d.h"

void			handle_move(t_env *e, int speed)
{
	t_point		*new_pos;

	if (!(new_pos = malloc(sizeof(*new_pos))))
		clean_exit(e);
	new_pos->x = e->pos->x + cos(e->angle * M_PI / 180) * speed;
	new_pos->y = e->pos->y - sin(e->angle * M_PI / 180) * speed;
	if (inside_map(new_pos, e) && is_wall(new_pos, e) == 0)
	{
		free(e->pos);
		e->pos = new_pos;
		launch_display(e);
	}
	else
		free(new_pos);
}

int				key_press(int key, t_env *e)
{
	if (key == 53)
	{
		clean(e);
		exit(0);
	}
	else if (key == 123 || key == 124)
	{
		if (key == 123)
			e->angle = adjust_angle(e->angle, 5);
		else if (key == 124)
			e->angle = adjust_angle(e->angle, -5);
		launch_display(e);
	}
	else if (key == 126)
		handle_move(e, 20);
	else if (key == 125)
		handle_move(e, -20);
	return (0);
}

float			adjust_angle(float angle, float inc)
{
	if (angle + inc >= 360)
		angle = angle + inc - 360;
	else if (angle + inc < 0)
		angle = angle + inc + 360;
	else
		angle = angle + inc;
	return (angle);
}
