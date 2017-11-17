/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   display.c                                          :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/05/24 18:19:34 by pde-maul          #+#    #+#             */
/*   Updated: 2017/05/29 14:16:38 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../includes/wolf3d.h"

void				launch_display(t_env *e)
{
	int				nb_ray;
	int				wall_height;

	nb_ray = 0;
	e->img = mlx_new_image(e->mlx, e->width, e->height);
	while (nb_ray < e->width)
	{
		wall_height = get_wall_height(nb_ray, e);
		draw_ray(wall_height, nb_ray, e);
		nb_ray++;
	}
	mlx_put_image_to_window(e->mlx, e->win, e->img, 0, 0);
}

int					get_wall_height(int nb_ray, t_env *e)
{
	float			dist;
	float			ray;
	float			vertical_dist;
	float			horizontal_dist;
	float			corrected_dist;

	ray = adjust_angle(e->angle, (e->fov / 2) - nb_ray * e->ang_btw_ray);
	horizontal_dist = get_horizontal_dist(e, ray);
	vertical_dist = get_vertical_dist(e, ray);
	dist = MIN(horizontal_dist, vertical_dist);
	e->color = get_wall_color(horizontal_dist, vertical_dist, dist, ray);
	corrected_dist = dist * cos((fabs(ray - e->angle)) * M_PI / 180);
	return ((e->cube * e->dist) / corrected_dist);
}

int					get_wall_color(float horizontal,
	float vertical, float dist, float ray)
{
	if (dist == horizontal && is_up_part(ray))
		return (0x2A9DA0);
	else if (dist == horizontal && !is_up_part(ray))
		return (0x127173);
	else if (dist == vertical && is_right_part(ray))
		return (0x2C7873);
	else if (dist == vertical && !is_right_part(ray))
		return (0x114748);
	return (0);
}

void				draw_ray(int wall_height, int nb_ray, t_env *e)
{
	int				line;
	int				max;
	int				min;

	line = 0;
	min = (e->height - wall_height) / 2;
	max = min + wall_height;
	while (line < e->height)
	{
		if (line <= min)
			pixel_put_to_image(e->sky_color, e, nb_ray, line);
		else if (line > min && line < max)
			pixel_put_to_image(e->color, e, nb_ray, line);
		else
			pixel_put_to_image(e->ground_color, e, nb_ray, line);
		line++;
	}
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
