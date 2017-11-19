class Movie
	def initialize
		@global_hash = Hash.new()
		$moviedex_hash = Hash.new()
	end

	def create
		5.times do
			@movie_mon = Hash.new()
			uri = URI('https://random-movie.herokuapp.com/random')
			movie = Net::HTTP.get(uri)
			parsed = JSON.parse(movie)
			@movie_mon = {'title' => parsed["Title"],
				'year' => parsed["Year"],
				'genre' => parsed["Genre"],
				'director' => parsed["Director"],
				'synopsis' => parsed["Plot"],
				'poster' => parsed["Poster"],
				'imdbRating' => parsed["imdbRating"].gsub(".", "").to_i}
			@global_hash[@movie_mon['title']] =  @movie_mon
		end
		return @global_hash
	end 
end
