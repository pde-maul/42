/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   main.c                                             :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/04/11 16:52:24 by pde-maul          #+#    #+#             */
/*   Updated: 2017/04/17 11:12:51 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "fractol.h"

void				put_string(t_env *e)
{
	char			*str;

	str = "Fract'ol";
	mlx_string_put(e->mlx, e->win, 250, 75, 0x021C1E, str);
	str = "Choose the Fractal:";
	mlx_string_put(e->mlx, e->win, 200, 125, 0xffffff, str);
	str = "Julia";
	mlx_string_put(e->mlx, e->win, 150, 265, 0xffffff, str);
	str = "Mandelbrot";
	mlx_string_put(e->mlx, e->win, 375, 265, 0xffffff, str);
	str = "Rabbit";
	mlx_string_put(e->mlx, e->win, 150, 415, 0xffffff, str);
	str = "Exit";
	mlx_string_put(e->mlx, e->win, 375, 415, 0xffffff, str);
}

void				pixel_put_to_image(int color, t_env *e, int x, int y)
{
	char			*data;
	unsigned long	lcolor;
	unsigned char	r;
	unsigned char	g;
	unsigned char	b;

	lcolor = mlx_get_color_value(e->mlx, color);
	data = mlx_get_data_addr(e->img, &e->bpp, &e->size_line, &e->endian);
	r = ((lcolor & 0xFF0000) >> 16);
	g = ((lcolor & 0xFF00) >> 8);
	b = ((lcolor & 0xFF));
	data[x * e->bpp / 8 + y * e->size_line] = b;
	data[x * e->bpp / 8 + 1 + y * e->size_line] = g;
	data[x * e->bpp / 8 + 2 + y * e->size_line] = r;
}

void				define_param(t_env *e)
{
	e->image_x = 600;
	e->image_y = 600;
	e->iteration_max = 20;
	e->mouse_x = 0;
	e->mouse_y = 0;
	e->color = 0x100000;
	e->x1 = -2;
	e->x2 = -2 + 4 * e->image_x / e->image_y;
	e->y1 = -2;
	e->y2 = 2;
	e->compt = 0;
	e->winj = NULL;
	e->imgj = NULL;
	e->mlxj = NULL;
}

int					main(void)
{
	t_env			*e;

	if (!(e = malloc(sizeof(t_env))))
		return (0);
	define_param(e);
	e->mlx = mlx_init();
	e->win = mlx_new_window(e->mlx, 600, 600, "Welcome screen");
	e->img = mlx_new_image(e->mlx, e->image_x, e->image_y);
	create_main_wind(e);
	create_julia_rectangle(e);
	create_mandelbrot_rectangle(e);
	create_rabbit_rectangle(e);
	create_newone_rectangle(e);
	put_string(e);
	mlx_key_hook(e->win, key_hook, e);
	mlx_mouse_hook(e->win, mouse_hook, e);
	mlx_loop(e->mlx);
	return (0);
}
