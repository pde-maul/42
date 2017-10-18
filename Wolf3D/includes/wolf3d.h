/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   wolf3d.h                                           :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/05/24 10:45:45 by pde-maul          #+#    #+#             */
/*   Updated: 2017/05/29 14:35:34 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#ifndef WOLF3D_H
# define WOLF3D_H

# include "../libft/libft.h"
# include "../minilibx_macos/mlx.h"
# include <math.h>
# include <fcntl.h>

# define MIN(X, Y) (((X) < (Y)) ? (X) : (Y))

typedef struct	s_point
{
	float		x;
	float		y;
}				t_point;

typedef	struct	s_env
{
	void		*mlx;
	void		*win;
	void		*img;

	int			nb_line;
	int			nb_col;
	int			**grid;
	float		cube;

	float		angle;
	int			fov;
	int			width;
	int			height;
	int			center_x;
	int			center_y;
	float		dist;
	float		ang_btw_ray;

	t_point		*pos;

	int			bpp;
	int			endian;
	int			size_line;
	int			i;
	int			j;

	int			sky_color;
	int			ground_color;
	int			color;

}				t_env;

void			define_param(t_env *e);
int				key_press(int key, t_env *e);
void			handle_move(t_env *e, int speed);
int				clean_exit(t_env *e);

int				check_digit(char **split, int *count, t_env *e);
int				check_grid(t_env *e, int fd);

void			read_line(int i, t_env *e, int fd);
void			read_grid(t_env *e, int fd);

void			clean(t_env *e);
void			launch_display(t_env *e);
int				get_wall_height(int nb_ray, t_env *e);
float			get_horizontal_dist(t_env *e, float ray_angle);
t_point			*get_next_horizontal_point(t_env *e, t_point *point,
				t_point *inc);
t_point			*get_first_horizontal_point(t_env *e, float ray_angle);

float			adjust_angle(float angle, float inc);
int				is_up_part(float angle);
int				inside_map(t_point *cross, t_env *e);
int				is_wall(t_point *cross, t_env *e);
float			get_dist(t_point *wall, t_env *e);

void			draw_ray(int wall_height, int nb_ray, t_env *e);
void			pixel_put_to_image(int color, t_env *e, int x, int y);

t_point			*get_first_vertical_point(t_env *e, float ray_angle);
int				is_right_part(float angle);
t_point			*get_next_vertical_point(t_env *e, t_point *point,
				t_point *inc);
float			get_vertical_dist(t_env *e, float ray_angle);

int				get_wall_color(float horizontal, float vertical,
				float dist, float ray);
void			clean_tab(char **tab);

#endif
