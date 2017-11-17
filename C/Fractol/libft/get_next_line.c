/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   get_next_line.c                                    :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/01/02 14:16:14 by pde-maul          #+#    #+#             */
/*   Updated: 2017/02/09 18:20:09 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

static char			*ft_erase(char *tmp, char *buff)
{
	char			*erase;

	erase = ft_strjoin(tmp, buff);
	free(tmp);
	tmp = ft_strdup(erase);
	free(erase);
	return (tmp);
}

int					get_next_line(const int fd, char **line)
{
	char			*i;
	char			buff[BUFF_SIZE + 1];
	static char		*tmp;
	int				ret_read;

	i = NULL;
	if (fd < 0)
		return (-1);
	if (!(tmp) && !(tmp = (char *)malloc(sizeof(char))))
		return (-1);
	while ((ret_read = read(fd, buff, BUFF_SIZE)) != 0)
	{
		buff[ret_read] = '\0';
		tmp = ft_erase(tmp, buff);
		ft_bzero(buff, BUFF_SIZE + 1);
		if ((i = ft_strchr(tmp, '\n')) != 0)
		{
			*line = ft_strsub(tmp, 0, (i - tmp));
			tmp = ft_strcpy(tmp, i + 1);
			break ;
		}
		if (ret_read == -1)
			return (-1);
	}
	return (ft_suitegnl(line, tmp, ret_read, i));
}

int					ft_suitegnl(char **line, char *tmp, int ret_read, char *i)
{
	char			*j;
	char			*chr;

	j = NULL;
	chr = NULL;
	if (i != NULL)
		return (1);
	if (tmp != '\0' && ret_read == 0)
	{
		if ((j = ft_strchr(tmp, '\n')))
		{
			*line = ft_strsub(tmp, 0, (j - tmp));
			tmp = ft_strcpy(tmp, j + 1);
			return (1);
		}
		if ((j = ft_strchr(tmp, '\0')) != NULL && (ft_strcmp(tmp, "\0") != 0))
		{
			*line = ft_strsub(tmp, 0, (j - tmp));
			chr = ft_strchr(tmp, '\0');
			tmp = ft_strcpy(tmp, chr);
			return (1);
		}
	}
	return (0);
}
