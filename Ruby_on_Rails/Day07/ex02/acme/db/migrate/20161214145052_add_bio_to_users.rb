class AddBioToUsers < ActiveRecord::Migration
  def change
    add_column :users, :bio, :text
    add_column :users, :name, :string
  end
end
