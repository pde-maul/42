/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   clean.c                                            :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/05/24 17:53:11 by pde-maul          #+#    #+#             */
/*   Updated: 2017/05/31 15:25:37 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../includes/wolf3d.h"

void			clean(t_env *e)
{
	int			i;

	i = 0;
	(e->pos != NULL) ? free(e->pos) : 0;
	(e->img != NULL) ? mlx_destroy_image(e->mlx, e->img) : 0;
	(e->win != NULL) ? mlx_destroy_window(e->mlx, e->win) : 0;
	while (i < e->nb_line)
	{
		free(e->grid[i]);
		i++;
	}
	free(e->grid);
}

int				clean_exit(t_env *e)
{
	clean(e);
	exit(0);
}

void			clean_tab(char **tab)
{
	int			i;
	int			len;

	i = 0;
	len = ft_tab_len(tab);
	while (i < len)
	{
		free(tab[i]);
		i++;
	}
	free(tab);
}
