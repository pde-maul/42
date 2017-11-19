class MoviemonController < ApplicationController

	def homepage
		@title = "MovieMon"
		$player_x = 8
		$player_y = 4
		$game = Battle.new
		$global_hash = $game.create
	end
	def nomovie
		@title = "MovieDex"
	end
	def load
		@title = "Load"
	end
	def moviedex
		@title = "MovieDex"
		if $game.moviemon.empty?
			return render :nomovie
		end
		if $game.whichmovie >= $game.moviemon.length
			$game.whichmovie = 0
		elsif $game.whichmovie < 0
			$game.whichmovie = $game.moviemon.length - 1
		end
		@moviename = $game.moviemon.keys[$game.whichmovie]
		@year = $game.moviemon[@moviename]['year']
		@genre = $game.moviemon[@moviename]['genre']
		@dir = $game.moviemon[@moviename]['director']
		@syn = $game.moviemon[@moviename]['synopsis']
		@img = $game.moviemon[@moviename]['poster']
	end

	def next
		$game.whichmovie += 1
		self.moviedex
		render :moviedex
	end

	def previous
		$game.whichmovie -= 1;
		self.moviedex
		render :moviedex
	end
end
