/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   create_main_win.c                                  :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/04/14 11:48:01 by pde-maul          #+#    #+#             */
/*   Updated: 2017/04/17 11:13:02 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fractol.h"

void		create_main_wind(t_env *e)
{
	int x;
	int y;

	x = 0;
	while (x++ < e->image_x)
	{
		y = 0;
		while (y++ < e->image_y)
			pixel_put_to_image(0x004445, e, x, y);
	}
	e->x1 = x;
	e->y1 = y;
	mlx_put_image_to_window(e->mlx, e->win, e->img, 0, 0);
}

void		create_julia_rectangle(t_env *e)
{
	int x;
	int y;

	x = 100;
	while (x++ < 250)
	{
		y = 250;
		while (y++ < 300)
			pixel_put_to_image(0x2C7873, e, x, y);
	}
	mlx_put_image_to_window(e->mlx, e->win, e->img, 0, 0);
}

void		create_mandelbrot_rectangle(t_env *e)
{
	int x;
	int y;

	x = 350;
	while (x++ < 500)
	{
		y = 250;
		while (y++ < 300)
			pixel_put_to_image(0x2C7873, e, x, y);
	}
	mlx_put_image_to_window(e->mlx, e->win, e->img, 0, 0);
}

void		create_rabbit_rectangle(t_env *e)
{
	int x;
	int y;

	x = 100;
	while (x++ < 250)
	{
		y = 400;
		while (y++ < 450)
			pixel_put_to_image(0x2C7873, e, x, y);
	}
	mlx_put_image_to_window(e->mlx, e->win, e->img, 0, 0);
}

void		create_newone_rectangle(t_env *e)
{
	int x;
	int y;

	x = 350;
	while (x++ < 500)
	{
		y = 400;
		while (y++ < 450)
			pixel_put_to_image(0x2C7873, e, x, y);
	}
	mlx_put_image_to_window(e->mlx, e->win, e->img, 0, 0);
}
