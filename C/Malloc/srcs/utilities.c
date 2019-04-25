/* ************************************************************************** */
/*                                                                            */
/*                                                        :::      ::::::::   */
/*   utilities.c                                        :+:      :+:    :+:   */
/*                                                    +:+ +:+         +:+     */
/*   By: jaylor <jaylor@student.42.fr>              +#+  +:+       +#+        */
/*                                                +#+#+#+#+#+   +#+           */
/*   Created: 2018/10/19 12:57:50 by pde-maul          #+#    #+#             */
/*   Updated: 2018/11/29 14:45:15 by jaylor           ###   ########.fr       */
/*                                                                            */
/* ************************************************************************** */

#include "../includes/malloc.h"

void	*check_between_nodes(t_pages *curr_p, size_t size)
{
	t_base_node *node;

	node = curr_p->head;
	while (node)
	{
		if (node->is_free == 1 && node->next != NULL && size <= node->size)
		{
			node->is_free = 0;
			return ((void *)node + sizeof(t_base_node));
		}
		node = node->next;
	}
	return (NULL);
}

size_t	get_optimal_size(size_t chunk_size, char letter)
{
	size_t initial;
	size_t shit;

	if (letter == 's')
	{
		initial = chunk_size * MALLOC_PER_PAGE;
		initial += sizeof(t_base_node) * MALLOC_PER_PAGE + sizeof(t_pages);
		shit = ((size_t)(initial / getpagesize()) + 1) * getpagesize();
		return (shit);
	}
	else
		return ((size_t)((chunk_size + sizeof(t_pages)) /
		getpagesize()) + 1) * getpagesize();
}

void	*new_mmap(size_t size, char letter)
{
	void *mmap_hold;

	mmap_hold = mmap(NULL, get_optimal_size(size, letter),
	PROT_READ | PROT_WRITE, MAP_ANON | MAP_PRIVATE, -1, 0);
	if (mmap_hold == MAP_FAILED)
		return (NULL);
	return (mmap_hold);
}

void	*find_spot(t_pages *current_page, size_t size)
{
	t_base_node *malloc_node;

	malloc_node = current_page->head;
	while (malloc_node->next != NULL)
		malloc_node = malloc_node->next;
	return (do_malloc(current_page, malloc_node, size));
}

void	ft_putstr(char const *s)
{
	int i;

	if (!s)
		return ;
	i = 0;
	while (s[i])
	{
		i++;
	}
	write(1, s, i);
}
