#!/usr/bin/env ruby -w

def my_var
	lines = File.open("numbers.txt", "r")
	lines2 = lines.read.split(/,?\n/).sort_by(&:to_i)
	puts lines2
end

my_var
