class CreateClassrooms < ActiveRecord::Migration
  def change
    create_table :classrooms do |t|
      t.string :room_number
      t.integer :capacity
      t.references :location, index: true, foreign_key: true

      t.timestamps null: false
    end
  end
end
