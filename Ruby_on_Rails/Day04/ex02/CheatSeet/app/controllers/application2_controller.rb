#msd de securite
class Application2Controller < ActionController::Base
  # Prevent CSRF attacks by raising an exception.
  # For APIs, you may want to use :null_session instead.
  protect_from_forgery with: :exception

def application
	@title = "Application"
end

def console
	@title = "Console"
end

def ruby
	@title = "Ruby"
end

def numbers
	@title = "Numbers"
end

def strings
	@title = "Strings"
end

def arrays
	@title = "Arrays"
end

def rails
	@title = "Rails"
end

def rails_app
	@title = "Rails App"
end

def rails_commands
	@title = "Rails_Commands"
end

def embedded_ruby
	@title = "ERB: Embedded Ruby"
end

def hashes
	@title = "Hashes"
end

def editor
	@title = "Editor Tips"
end

def quicksearch
	@title = "Quick Search"
end

def logbook
  @title = "Log Book"
  # @file_content =
end

def write
    t = Time.zone.now
    file_content = File.open('entry_log.txt', 'r') {|f| f.read }
    File.open('entry_log.txt', 'w') {|f|
      f.puts t.strftime("%d/%m/%Y %H:%M:%S : #{params[:text][:post]}<br>")
      f.puts file_content
    }
    redirect_to action: :logbook
  end
end
