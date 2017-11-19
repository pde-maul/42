class Product < ActiveRecord::Base
	belongs_to :brand
	mount_uploader :pict, AvatarUploader
end
