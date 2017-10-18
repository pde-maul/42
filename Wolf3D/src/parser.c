/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   parser.c                                           :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/05/24 11:00:58 by pde-maul          #+#    #+#             */
/*   Updated: 2017/10/18 19:15:24 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../includes/wolf3d.h"

int			check_digit(char **split, int *count, t_env *e)
{
	int i;
	int j;

	i = 0;
	while (split[i])
	{
		j = 0;
		while (split[i][j])
		{
			// if (!ft_isdigit(split[i][j]))
			// 	return (-1);
			j++;
		}
		if (ft_atoi(split[i]) != 0 && ft_atoi(split[i]) != 1
			&& ft_atoi(split[i]) != 2)
			return (-1);
		if (ft_atoi(split[i]) == 2)
		{
			(*count)++;
			e->i = i;
			e->j = e->nb_line;
		}
		i++;
	}
	return (1);
}

int			check_grid(t_env *e, int fd)
{
	char	*line;
	int		ret;
	char	**split;
	int		count;

	line = NULL;
	e->nb_line = 0;
	e->nb_col = 0;
	count = 0;
	while ((ret = get_next_line(fd, &line)) == 1)
	{
		split = ft_strsplit(line, ' ');
		free(line);
		if (e->nb_col == 0)
			e->nb_col = ft_tab_len(split);
		else if (e->nb_col != ft_tab_len(split))
			return (-1);
		if (check_digit(split, &count, e) == -1)
			return (-1);
		e->nb_line++;
		clean_tab(split);
	}
	close(fd);
	return ((count != 1) ? -1 : (ret));
}

void		read_line(int i, t_env *e, int fd)
{
	char	*line;
	int		j;
	char	**split;

	line = NULL;
	j = 0;
	get_next_line(fd, &line);
	split = ft_strsplit(line, ' ');
	free(line);
	if (!(e->grid[i] = (int*)malloc(sizeof(int) * e->nb_col)))
		clean_exit(e);
	while (j < e->nb_col)
	{
		e->grid[i][j] = ft_atoi(split[j]);
		j++;
	}
	clean_tab(split);
}

void		read_grid(t_env *e, int fd)
{
	int i;

	i = 0;
	if (!(e->grid = (int **)malloc(sizeof(int *) * e->nb_col)))
		clean_exit(e);
	while (i < e->nb_line)
	{
		read_line(i, e, fd);
		i++;
	}
	close(fd);
}
