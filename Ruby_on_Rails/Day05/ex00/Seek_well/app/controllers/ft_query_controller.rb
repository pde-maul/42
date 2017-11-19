class FtQueryController < ApplicationController
  def index() end

  def create_db
    $db = SQLite3::Database.new('db/test.db')
    redirect_to action: :index
  end

  def create_table
    $db = SQLite3::Database.open('db/test.db')
    $db.execute "CREATE TABLE IF NOT EXISTS clock_watch(
	  ts_Id INTEGER PRIMARY KEY,
      day INT,
	  month INT,
	  year INT,
	  hour INT,
	  min INT,
	  sec INT,
	  race INT,
	  name STRING(50),
	  lap INT);"
    $db.execute 'INSERT INTO clock_watch VALUES(1, 12, 12, 2016, 13, 18, 35, 1, bonjour, 1);'
    $db.execute 'CREATE TABLE IF NOT EXISTS race(ts_Id INTEGER PRIMARY KEY,
      name STRING(50));'
    $db.execute 'INSERT INTO race VALUES();'
    $db.close if $db
    redirect_to action: :index
  end

  def start_race() end

  def insert_time_stamp() end

  def drop_table() end

  def delete_last() end

  def destroy_all() end

  def all_by_name() end

  def all_by_race() end

  def update_time_stamp() end

  # private
end
