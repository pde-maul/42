class CreateProducts < ActiveRecord::Migration
  def change
    create_table :products do |t|

      t.timestamps null: false
	  t.string :name
	  t.string :pict
	  t.text :description
	  t.integer :brand_id
	  t.float :price
    end
  end
end
