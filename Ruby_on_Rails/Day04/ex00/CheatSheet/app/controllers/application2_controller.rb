#non non
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


end
