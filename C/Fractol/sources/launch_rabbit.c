/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   launch_rabbit.c                                    :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/04/14 14:01:30 by pde-maul          #+#    #+#             */
/*   Updated: 2017/04/17 11:12:46 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fractol.h"

void		main_rabbit(t_env *e)
{
	define_param_julia(e);
	e->mlxj = mlx_init();
	e->winj = mlx_new_window(e->mlxj, 600, 600, "Rabbit");
	launch_rabbit(e);
	mlx_key_hook(e->winj, key_hook2, e);
	mlx_mouse_hook(e->winj, mouse_hook_rabbit, e);
	mlx_loop(e->mlxj);
}

void		launch_rabbit(t_env *e)
{
	double	x;
	double	y;

	e->imgj = mlx_new_image(e->mlxj, e->imagej_x, e->imagej_y);
	x = -1;
	while (++x < e->imagej_x)
	{
		y = -1;
		while (++y < e->imagej_y)
			rabbit(e, x, y);
	}
	mlx_put_image_to_window(e->mlxj, e->winj, e->imgj, 0, 0);
}

void		rabbit(t_env *e, int x, int y)
{
	t_comp	z;
	t_comp	c;
	t_comp	tmp;
	int		i;

	c.x = -0.122565;
	c.y = -0.744864;
	i = 0;
	z.x = (double)x / ((double)e->image_x / (e->x2 - e->x1)) + e->x1;
	z.y = (double)y / ((double)e->image_y / (e->y2 - e->y1)) + e->y1;
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

void		pixel_put_to_image2(int color, t_env *e, int x, int y)
{
	char			*data;
	unsigned long	lcolor;
	unsigned char	r;
	unsigned char	g;
	unsigned char	b;

	lcolor = mlx_get_color_value(e->mlxj, color);
	data = mlx_get_data_addr(e->imgj, &e->bpp, &e->size_line, &e->endian);
	r = ((lcolor & 0xFF0000) >> 16);
	g = ((lcolor & 0xFF00) >> 8);
	b = ((lcolor & 0xFF));
	data[x * e->bpp / 8 + y * e->size_line] = b;
	data[x * e->bpp / 8 + 1 + y * e->size_line] = g;
	data[x * e->bpp / 8 + 2 + y * e->size_line] = r;
}
