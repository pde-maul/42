/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   launch_julia.c                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/04/14 12:12:24 by pde-maul          #+#    #+#             */
/*   Updated: 2017/04/17 11:12:28 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fractol.h"

void		main_julia(t_env *e)
{
	define_param_julia(e);
	e->mlxj = mlx_init();
	e->winj = mlx_new_window(e->mlxj, 600, 600, "julia");
	launch_julia(e);
	mlx_key_hook(e->winj, key_hook2, e);
	mlx_mouse_hook(e->winj, mouse_hook_julia, e);
	mlx_hook(e->winj, 6, (1L << 6), mouse_position, e);
	mlx_loop(e->mlxj);
}

void		launch_julia(t_env *e)
{
	double	x;
	double	y;

	e->imgj = mlx_new_image(e->mlxj, e->imagej_x, e->imagej_y);
	x = -1;
	while (++x < e->imagej_x)
	{
		y = -1;
		while (++y < e->imagej_y)
			julia(e, x, y);
	}
	mlx_put_image_to_window(e->mlxj, e->winj, e->imgj, 0, 0);
}

void		define_param_julia(t_env *e)
{
	e->imagej_x = 600;
	e->imagej_y = 600;
	e->iteration_max = 20;
	e->mousej_x = 0;
	e->mousej_y = 0;
	e->color = 0x100000;
	e->x1 = -2;
	e->x2 = -2 + 4 * e->imagej_x / e->imagej_y;
	e->y1 = -2;
	e->y2 = 2;
}

void		julia(t_env *e, int x, int y)
{
	t_comp	z;
	t_comp	c;
	t_comp	tmp;
	int		i;

	i = 0;
	z.x = (double)x / ((double)e->image_x / (e->x2 - e->x1)) + e->x1;
	z.y = (double)y / ((double)e->image_y / (e->y2 - e->y1)) + e->y1;
	c.x = e->mouse_x;
	c.y = e->mouse_y;
	while (((z.x * z.x) + (z.y * z.y) < 4 && i < e->iteration_max))
	{
		tmp.x = z.x;
		tmp.y = z.y;
		z.x = tmp.x * tmp.x - tmp.y * tmp.y + c.x;
		z.y = 2 * tmp.y * tmp.x + c.y;
		i++;
	}
	if (i == e->iteration_max)
		pixel_put_to_image2(e->color, e, x, y);
	else
		pixel_put_to_image2(e->color + (10000 * i), e, x, y);
}
