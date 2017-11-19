class PlayController < ApplicationController
	def map
		@title = "MovieMon"
	end

	def fight
		gamend = $game.player_hit
		@energy_player = gamend[2]
		puts $global_hash.first
		if $global_hash.empty?
			return render :finish
		end
		@moviebattle = $global_hash.first[1]
		@energy_moviemon = gamend[1]
		if gamend[0] == 0
			render :loose
		elsif gamend[0] == 1
			render :success
		else
			render :fight
		end
	end
	def up
		if ($game.player_x > 0)
			$game.player_x -= 1
			if rand(0..4) == 0
				self.fight
			else
				render :map
			end
		else
			render :map
		end
	end

	def down
		if ($game.player_x < 9)
			$game.player_x += 1
			if rand(0..4) == 0
				self.fight
			else
				render :map
			end
		else
			render :map
		end
	end

	def right
		if ($game.player_y < 9)
			$game.player_y += 1
			if rand(0..4) == 0
				self.fight
			else
				render :map
			end
		else
			render :map
		end
	end

	def left
		puts "player_y"
		puts $game.player_y
		if ($game.player_y > 0)
			$game.player_y -= 1
			puts "player_y_next"
			puts $game.player_y
			if rand(0..4) == 0
				self.fight
			else
				render :map
			end
		else
			render :map
		end
	end
	def success
	end
	def loose
	end
	def finish
	end
	def run
		$game.reset
	end
	def save
		a = {}
		a = $game.player
		File.open("save_de_ouf#{$game.save_val}.json", "w") {|f| f.write JSON.generate(a)}
		render :savepage
	end
	def loadpage
	end
	def load
		puts "toto"
		file = File.open("save_de_ouf#{$game.save_val}.json", "r").read
		json = JSON.parse(file)
		$global_hash = json['global_hash']
		$game.player_hit_point = json['player_hit_point']
		$game.player_x = json['player_position_x']
		$game.player_y = json['player_position_y']
		$game.moviemon = json['moviedex_hash']
		render :map
	end

	def savepage
		$game.save_val ||= 1
		@title = "Save"
	end
	def upsave
		$game.save_val = ($game.save_val + 1) % 3 +1
		render :savepage
	end
	def downsave
		$game.save_val = ($game.save_val) % 3 +1
		render :savepage
	end
	def upload
		$game.save_val = ($game.save_val + 1) % 3 +1
		render :loadpage
	end
	def download
		$game.save_val = ($game.save_val) % 3 +1
		render :loadpage
	end
end
