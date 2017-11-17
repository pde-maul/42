/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   ft_clean_tab.c                                     :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2017/05/24 11:45:59 by pde-maul          #+#    #+#             */
/*   Updated: 2017/05/24 11:49:11 by pde-maul         ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "libft.h"

void			ft_clean_tab(char **tab)
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
