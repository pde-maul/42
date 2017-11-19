class Brand < ActiveRecord::Base
	has_many :products
	mount_uploader :avatar, AvatarUploader
end
