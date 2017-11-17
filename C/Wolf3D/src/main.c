/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   main.c                                             :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/05/24 10:47:26 by pde-maul          #+#    #+#             */
/*   Updated: 2017/10/18 18:43:51 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../includes/wolf3d.h"

void		define_param(t_env *e)
{
	t_point	*pos;

	if (!(pos = (t_point*)malloc(sizeof(t_point))))
		clean_exit(e);
	e->pos = pos;
	e->angle = 90;
	e->width = 1000;
	e->height = 800;
	e->center_x = e->width / 2;
	e->center_y = e->height / 2;
	e->angle = 90;
	e->fov = 70;
	e->cube = 100;
	e->dist = (e->center_x / tan((e->fov / 2) * M_PI / 180));
	pos->x = e->i * e->cube + e->cube / 2;
	pos->y = e->j * e->cube + e->cube / 2;
	e->ang_btw_ray = e->fov / (float)e->width;
	e->ground_color = 0x1E1E1E;
	e->sky_color = 0xACE5F0;
}

int			main(int ac, char **av)
{
	t_env	*e;
	int		ret;

	if (!(e = (t_env*)malloc(sizeof(t_env))))
		clean_exit(e);
	ret = check_grid(e, open(av[1], O_RDONLY));
	if (ac < 2 || ret == -1 || e->nb_line < 1 || e->nb_col < 1)
	{
		printf("ac= %d, ret= %d, nb_line= %d, nb_col= %d\n", ac, ret, e->nb_line, e->nb_col);
		ft_putendl("Map error, please use a valid file.");
		free(e);
		exit(1);
	}
	read_grid(e, open(av[1], O_RDONLY));
	define_param(e);
	e->mlx = mlx_init();
	e->win = mlx_new_window(e->mlx, e->width, e->height, "Wolf3d");
	launch_display(e);
	mlx_hook(e->win, 2, (1L << 0), key_press, e);
	mlx_hook(e->win, 17, (1L << 17), clean_exit, e);
	mlx_loop(e->mlx);
	return (0);
}
