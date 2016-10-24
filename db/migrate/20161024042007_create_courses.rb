class CreateCourses < ActiveRecord::Migration
  def change
    create_table :courses do |t|
      t.string :code
      t.string :name
      t.references :program, index: true, foreign_key: true
      t.integer :credit_hours

      t.timestamps null: false
    end
  end
end
