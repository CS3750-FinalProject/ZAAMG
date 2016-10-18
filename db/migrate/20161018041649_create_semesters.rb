class CreateSemesters < ActiveRecord::Migration
  def change
    create_table :semesters do |t|
      t.string :year
      t.string :season

      t.timestamps null: false
    end
  end
end
