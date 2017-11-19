# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rake db:seed (or created alongside the db with db:setup).
#
# Examples:
#
#   cities = City.create([{ name: 'Chicago' }, { name: 'Copenhagen' }])
#   Mayor.create(name: 'Emanuel', city: cities.first)


User.create!(bio: FFaker::HipsterIpsum.paragraph,
name: 'admin',
email:'admin@gmail.com',
password:'password',
password_confirmation: 'password' )

50.times do |tm|
mk = Brand.create!(name: FFaker::Product.brand,
avatar: open(FFaker::Avatar.image))
50.times do |tw|
Product.create!(name: FFaker::Product.product,
pict: open(FFaker::Avatar.image),
description: FFaker::HipsterIpsum.paragraph,
brand_id: mk.id, price:price.sample)
end
end
