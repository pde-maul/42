#!/usr/bin/env ruby -w

class Html
	attr_reader :title

	def initialize(title)
		@page_name = title
		self.head
	end

	def head
		file = File.open("#{@page_name}.html", "w")
		file << "<!DOCTYPE html>\n"
		file << "<html>\n"
		file << "<head>\n"
		file << "<title>#{@page_name}</title>\n"
		file << "</head>\n"
		file << "<body>\n"
		file.close
	end

	def dump(str)
		file = File.open("#{@page_name}.html", "a")
		file << "<p>#{str}</p>\n"
		file.close
	end

	def finish
		file = File.open("#{@page_name}.html", "a")
		file.write "</body>"
	end

end

if $PROGRAM_NAME == __FILE__
	a = Html.new("test")
	10.times{|x| a.dump("titi_numbers#{x}")}
	a.finish
end
