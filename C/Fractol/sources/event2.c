/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   event2.c                                           :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/04/14 14:57:28 by pde-maul          #+#    #+#             */
/*   Updated: 2017/04/17 11:12:58 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fractol.h"

int		mouse_hook_julia(int button, int x, int y, t_env *e)
{
	t_comp	comp;
	double	xnew;
	double	ynew;

	xnew = e->x2 - e->x1;
	ynew = e->y2 - e->y1;
	comp.x = x / (e->imagej_x / (e->x2 - e->x1)) + e->x1;
	comp.y = y / (e->imagej_y / (e->y2 - e->y1)) + e->y1;
	if (button == 5)
		zoom_in(e, xnew, ynew, comp);
	if (button == 4)
		zoom_out(e, xnew, ynew);
	mlx_destroy_image(e->mlxj, e->imgj);
	launch_julia(e);
	return (0);
}

int		mouse_hook_rabbit(int button, int x, int y, t_env *e)
{
	t_comp	comp;
	double	xnew;
	double	ynew;

	xnew = e->x2 - e->x1;
	ynew = e->y2 - e->y1;
	comp.x = x / (e->imagej_x / (e->x2 - e->x1)) + e->x1;
	comp.y = y / (e->imagej_y / (e->y2 - e->y1)) + e->y1;
	if (button == 5)
		zoom_in(e, xnew, ynew, comp);
	if (button == 4)
		zoom_out(e, xnew, ynew);
	mlx_destroy_image(e->mlxj, e->imgj);
	launch_rabbit(e);
	return (0);
}

int		mouse_hook_mandelbrot(int button, int x, int y, t_env *e)
{
	t_comp	comp;
	double	xnew;
	double	ynew;

	xnew = e->x2 - e->x1;
	ynew = e->y2 - e->y1;
	comp.x = x / (e->imagej_x / (e->x2 - e->x1)) + e->x1;
	comp.y = y / (e->imagej_y / (e->y2 - e->y1)) + e->y1;
	if (button == 5)
		zoom_in(e, xnew, ynew, comp);
	if (button == 4)
		zoom_out(e, xnew, ynew);
	mlx_destroy_image(e->mlxj, e->imgj);
	launch_mandelbrot(e);
	return (0);
}

int		key_hook2(int keycode, t_env *e)
{
	if (keycode == 53)
	{
		mlx_destroy_window(e->mlxj, e->winj);
		e->mlxj = 0;
		e->winj = 0;
		e->imgj = 0;
		e->compt = 0;
	}
	return (0);
}

int		mouse_position2(int x, int y, t_env *e)
{
	e->mousej_x = x / (e->imagej_x / (e->x2 - e->x1)) + e->x1;
	e->mousej_y = y / (e->imagej_y / (e->y2 - e->y1)) + e->y1;
	e->color += 10;
	mlx_destroy_image(e->mlxj, e->imgj);
	launch_julia(e);
	return (0);
}
