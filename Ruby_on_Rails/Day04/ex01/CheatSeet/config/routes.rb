Rails.application.routes.draw do
  # The priority is based upon order of creation: first created -> highest priority.
  # See how all your routes lay out with "rake routes".

  # You can have the root of your site routed with "root"
  # root 'welcome#index'

  # Example of regular route:
    root 'application2#convention'
    get '/console' => 'application2#console'
	get '/ruby' => 'application2#ruby'
	get '/ruby/numbers' => 'application2#numbers'
	get '/ruby/strings' => 'application2#strings'
	get '/ruby/arrays' => 'application2#arrays'
	get '/ruby/hashes' => 'application2#hashes'
	get '/rails' => 'application2#rails'
	get '/rails/rails_app' => 'application2#rails_app'
	get '/rails/rails_commands' => 'application2#rails_commands'
	get '/rails/embedded_ruby' => 'application2#embedded_ruby'
	get '/editor' => 'application2#editor'
	get '/quicksearch' => 'application2#quicksearch'
	# get '/help' => 'application2#help'

  # Example of named route that can be invoked with purchase_url(id: product.id)
  #   get 'products/:id/purchase' => 'catalog#purchase', as: :purchase

  # Example resource route (maps HTTP verbs to controller actions automatically):
  #   resources :products

  # Example resource route with options:
  #   resources :products do
  #     member do
  #       get 'short'
  #       post 'toggle'
  #     end
  #
  #     collection do
  #       get 'sold'
  #     end
  #   end

  # Example resource route with sub-resources:
  #   resources :products do
  #     resources :comments, :sales
  #     resource :seller
  #   end

  # Example resource route with more complex sub-resources:
  #   resources :products do
  #     resources :comments
  #     resources :sales do
  #       get 'recent', on: :collection
  #     end
  #   end

  # Example resource route with concerns:
  #   concern :toggleable do
  #     post 'toggle'
  #   end
  #   resources :posts, concerns: :toggleable
  #   resources :photos, concerns: :toggleable

  # Example resource route within a namespace:
  #   namespace :admin do
  #     # Directs /admin/products/* to Admin::ProductsController
  #     # (app/controllers/admin/products_controller.rb)
  #     resources :products
  #   end
end
