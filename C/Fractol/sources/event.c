/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   event.c                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/03/15 15:19:02 by pde-maul          #+#    #+#             */
/*   Updated: 2017/04/17 11:13:33 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fractol.h"

int		key_hook(int keycode)
{
	if (keycode == 53)
		exit(0);
	return (0);
}

int		mouse_position(int x, int y, t_env *e)
{
	e->mouse_x = x / (e->image_x / (e->x2 - e->x1)) + e->x1;
	e->mouse_y = y / (e->image_y / (e->y2 - e->y1)) + e->y1;
	e->color += 10;
	mlx_destroy_image(e->mlxj, e->imgj);
	launch_julia(e);
	return (0);
}

void	zoom_in(t_env *e, double xnew, double ynew, t_comp comp)
{
	double tmp;

	tmp = e->x1;
	e->x1 = (comp.x + (e->x2 + e->x1) / 2) / 2 - (xnew * 0.4);
	e->x2 = (comp.x + (e->x2 + tmp) / 2) / 2 + (xnew * 0.4);
	tmp = e->y1;
	e->y1 = (comp.y + (e->y2 + e->y1) / 2) / 2 - (ynew * 0.4);
	e->y2 = (comp.y + (e->y2 + tmp) / 2) / 2 + (ynew * 0.4);
	e->iteration_max += 1;
}

void	zoom_out(t_env *e, double xnew, double ynew)
{
	e->x1 = e->x1 - (xnew * 0.5);
	e->x2 = e->x2 + (xnew * 0.5);
	e->y1 = e->y1 - (ynew * 0.5);
	e->y2 = e->y2 + (ynew * 0.5);
	e->iteration_max -= 1;
}

int		mouse_hook(int button, int x, int y, t_env *e)
{
	e->mouse_x = x;
	e->mouse_y = y;
	if (button == 1 && e->mouse_x > 100 && e->mouse_x < 250 && e->mouse_y > 250\
		&& e->mouse_y < 300 && e->compt++ < 1)
		main_julia(e);
	if (button == 1 && e->mouse_x > 350 && e->mouse_x < 500 && e->mouse_y > 250\
		&& e->mouse_y < 300 && e->compt++ < 1)
		main_mandelbrot(e);
	if (button == 1 && e->mouse_x > 100 && e->mouse_x < 250 && e->mouse_y > 400\
		&& e->mouse_y < 450 && e->compt++ < 1)
		main_rabbit(e);
	if (button == 1 && e->mouse_x > 350 && e->mouse_x < 500 && e->mouse_y > 400\
		&& e->mouse_y < 450)
	{
		if (e->mlxj || e->winj)
			mlx_destroy_window(e->mlxj, e->winj);
		exit(0);
	}
	return (0);
}
