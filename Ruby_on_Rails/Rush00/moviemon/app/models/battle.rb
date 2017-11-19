class Battle
	attr_accessor :save_val, :player_x, :player_y, :moviemon, :whichmovie, :player_hit_point
	def initialize
		@player_energy = 100
		@movie_mon_energy = 100
		$global_hash = Hash.new()
		@moviemon = Hash.new()
		@save_val = 1
		@player_x = rand(0..9)
		@player_y = rand(0..9)
		@whichmovie = 0
		@player_hit_point ||= 100
	end
	def create
		moyennes_hit = 0
		4.times do
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
			$global_hash[@movie_mon['title']] =  @movie_mon
			@movie_mon_hit_point = @movie_mon['imdbRating']
			moyennes_hit += @movie_mon_hit_point
		end
		@player_hit_point = moyennes_hit / 2
		@player_hit_point /= 5
		return $global_hash
	end

	def player_hit
		@energy = []
		@energy[0] = 2
		@energy[1] = @movie_mon_energy
		@energy[2] = @player_energy
		@movie_mon_energy = @movie_mon_energy - @player_hit_point
		self.movie_mon_hit
	end

	def movie_mon_hit
		@player_energy = @player_energy  - (@movie_mon_hit_point/5)
		self.battle2
	end
	def battle2
		if @player_energy <= 0
			$global_hash = $global_hash.to_a
			$global_hash.delete_at(0)
			$global_hash = $global_hash.to_h
			@player_energy = 100
			@movie_mon_energy = 100
			@energy[0] = 0
			puts $global_hash
			return @energy
		elsif @movie_mon_energy <= 0
			$game.moviemon[$global_hash.first[0]] =  $global_hash.first[1]
			$global_hash = $global_hash.to_a
			$global_hash.delete_at(0)
			$global_hash = $global_hash.to_h
			@player_hit_point += rand(0..10)
			@player_energy = 100
			@movie_mon_energy = 100
			@energy[0] = 1
			return @energy
		end
		return @energy
	end
	def reset
		@player_energy = 100
		@movie_mon_energy = 100
		$global_hash = $global_hash.to_a
		$global_hash.delete_at(0)
		$global_hash = $global_hash.to_h
	end

	def player
		save = Hash.new()
		save = {
			'player_hit_point' => @player_hit_point,
			'player_position_x' => $game.player_x,
			'player_position_y' => $game.player_y,
			'moviemon' => $game.moviemon,
			'global_hash' => $global_hash
		}
		return save
	end
end
