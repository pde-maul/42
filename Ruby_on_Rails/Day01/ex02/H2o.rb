#!/usr/bin/env ruby -w

def my_var
	hashy = Hash.new()
	data = [['Caleb' , 24],
			['Calixte' , 84],
			['Calliste', 65],
			['Calvin' , 12],
			['Cameron' , 54],
			['Camil' , 32],
			['Camille' , 5],
			['Can' , 52],
			['Caner' , 56],
			['Cantin' , 4],
			['Carl' , 1],
			['Carlito' , 23],
			['Carlo' , 19],
			['Carlos' , 26],
			['Carter' , 54],
			['Casey' , 2]
		]
		data.each do |cell|
			hashy[cell[0]] = cell[1]
		end
		hashy.each {|k, v| puts "#{k} : #{v}"}
end

my_var
