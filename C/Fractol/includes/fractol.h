/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   fractol.h                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/04/17 10:26:50 by pde-maul          #+#    #+#             */
/*   Updated: 2017/04/17 11:48:07 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#ifndef FRACTOL_H
# define FRACTOL_H
# include <math.h>
# include "../minilibx_macos/mlx.h"
# include <stdio.h>
# include <stdlib.h>
# include "../libft/libft.h"

typedef struct	s_env
{
	void		*mlx;
	void		*img;
	void		*win;
	double		image_x;
	double		image_y;

	double		mouse_x;
	double		mouse_y;

	double		x1;
	double		x2;
	double		y1;
	double		y2;

	int			bpp;
	int			endian;
	int			size_line;
	int			iteration_max;
	int			color;

	void		*mlxj;
	void		*imgj;
	void		*winj;
	double		imagej_x;
	double		imagej_y;
	double		mousej_x;
	double		mousej_y;
	int			compt;

}				t_env;

typedef struct	s_comp
{
	double		x;
	double		y;
}				t_comp;

int				key_hook(int keycode);
int				mouse_position(int x, int y, t_env *e);
int				mouse_hook(int button, int x, int y, t_env *e);
void			create_main_wind(t_env *e);
void			create_julia_rectangle(t_env *e);
void			create_mandelbrot_rectangle(t_env *e);
void			create_rabbit_rectangle(t_env *e);
void			create_newone_rectangle(t_env *e);
void			pixel_put_to_image(int color, t_env *e, int x, int y);
void			put_string(t_env *e);
void			define_param(t_env *e);
void			zoom_in(t_env *e, double xnew, double ynew, t_comp comp);
void			zoom_out(t_env *e, double xnew, double ynew);

void			main_julia(t_env *e);
void			launch_julia(t_env *e);
void			julia(t_env *e, int x, int y);
void			define_param_julia(t_env *e);
int				mouse_hook_julia(int button, int x, int y, t_env *e);
int				mouse_position2(int x, int y, t_env *e);

void			main_mandelbrot(t_env *e);
void			mandelbrot(t_env *e, int x, int y);
void			launch_mandelbrot(t_env *e);
int				mouse_hook_mandelbrot(int button, int x, int y, t_env *e);

void			launch_rabbit(t_env *e);
void			main_rabbit(t_env *e);
void			rabbit(t_env *e, int x, int y);
void			pixel_put_to_image2(int color, t_env *e, int x, int y);
int				mouse_hook_rabbit(int button, int x, int y, t_env *e);
int				key_hook2(int keycode, t_env *e);

void			launch_fourth(t_env *e);
void			main_fourth(t_env *e);
void			fourth(t_env *e, int x, int y);

#endif
