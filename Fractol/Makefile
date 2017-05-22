# **************************************************************************** #
#                                                                              #
#                                                         :::      ::::::::    #
#    Makefile                                           :+:      :+:    :+:    #
#                                                     +:+ +:+         +:+      #
#    By: pde-maul <pde-maul@student.42.fr>          +#+  +:+       +#+         #
#                                                 +#+#+#+#+#+   +#+            #
#    Created: 2017/02/27 17:11:00 by pde-maul          #+#    #+#              #
#    Updated: 2017/04/17 11:46:55 by pde-maul         ###   ########.fr        #
#                                                                              #
# **************************************************************************** #

NAME = fractol

CC = gcc

FLAG = -Wall -Werror -Wextra -I $(INC_DIR)

# gcc main.c -L. -lmlx -L libft/ -lft -framework OpenGL -framework AppKit
INC_DIR = includes/

SRC_DIR = sources/

SRC = 	main.c \
		event.c \
		create_main_win.c \
		launch_julia.c \
		launch_mandelbrot.c \
		launch_rabbit.c \
		event2.c \

OBJ = $(SRC:.c=.o)

I_LIBFT = -Ilibft

LIBFT = -L libft/ -lft -I libft/

LIBMLX =  -L./minilibx_macos -lmlx -lm -framework OpenGL -framework AppKit\
 			-Iminilibx_macos

.PHONY:clean re all fclean

all: $(NAME)

$(NAME): $(OBJ)
	@make -C libft
	@$(CC) $(FLAG) -o $(NAME) $^ $(LIBFT) $(LIBMLX)
	@echo "\033[33;32m $(NAME) is ready :)"

%.o: $(SRC_DIR)%.c
	@$(CC) $(FLAG) -o $@ -c $<  $(I_LIBFT)

clean:
	@make clean -C libft
	@rm -f $(OBJ)
	@echo "\x1b[36mclean ok"

fclean: clean
	@make fclean -C libft
	@rm -f $(NAME)
	@echo "\x1b[36mfclean ok"

re: fclean all
