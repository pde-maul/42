require 'Nokogiri'
require 'open-uri'

class UsersController < ApplicationController

  def new
	  @user= User.new
	  url = "https://www.randomlists.com/random-animals"
	  doc = Nokogiri::HTML(open(url))
	  @animal_name = doc.css('li').first.css('span').text
		if cookies[:user_name].blank?
	   		cookies[:user_name] = { value: @animal_name, expires: 1.minute.from_now }
		else
			@animal_name = cookies[:user_name]
		end
  end

  def create
	  @user = User.new(user_params)
	  if @user.save
		  session[:user_id] = @user.id
		  @animal_name = session[:user_name]
		  redirect_to root_url, :notice => "signed up!"
	  else
		  render "new"
	  end
  end

  def user_params
    params.require(:user).permit(:name, :email, :password, :password_confirmation)
  end
 end
