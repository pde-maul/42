/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   launch_mandelbrot.c                                :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/04/14 13:49:31 by pde-maul          #+#    #+#             */
/*   Updated: 2017/04/17 11:12:41 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fractol.h"

void			main_mandelbrot(t_env *e)
{
	define_param_julia(e);
	e->mlxj = mlx_init();
	e->winj = mlx_new_window(e->mlxj, 600, 600, "Mandelbrot");
	launch_mandelbrot(e);
	mlx_key_hook(e->winj, key_hook2, e);
	mlx_mouse_hook(e->winj, mouse_hook_mandelbrot, e);
	mlx_loop(e->mlxj);
}

void			launch_mandelbrot(t_env *e)
{
	double	x;
	double	y;

	e->imgj = mlx_new_image(e->mlxj, e->imagej_x, e->imagej_y);
	x = -1;
	while (++x < e->imagej_x)
	{
		y = -1;
		while (++y < e->imagej_y)
		{
			mandelbrot(e, x, y);
		}
	}
	mlx_put_image_to_window(e->mlxj, e->winj, e->imgj, 0, 0);
}

void			mandelbrot(t_env *e, int x, int y)
{
	t_comp	z;
	t_comp	c;
	t_comp	tmp;
	int		i;

	i = 0;
	c.x = (double)x / ((double)e->imagej_x / (e->x2 - e->x1)) + e->x1;
	c.y = (double)y / ((double)e->imagej_y / (e->y2 - e->y1)) + e->y1;
	z.x = 0.0;
	z.y = 0.0;
	while (((z.x * z.x) + (z.y * z.y)) < 4 && i < e->iteration_max)
	{
		tmp.x = z.x;
		tmp.y = z.y;
		z.x = tmp.x * tmp.x - tmp.y * tmp.y + c.x;
		z.y = 2 * tmp.x * tmp.y + c.y;
		i++;
	}
	if (i == e->iteration_max)
		pixel_put_to_image2(e->color, e, x, y);
	else
		pixel_put_to_image2(e->color + (10000 * i), e, x, y);
}
