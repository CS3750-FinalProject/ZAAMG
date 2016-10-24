class CreateScheduledCourses < ActiveRecord::Migration
  def change
    create_table :scheduled_courses do |t|
      t.references :semester, index: true, foreign_key: true
      t.references :course, index: true, foreign_key: true
      t.references :professor, index: true, foreign_key: true
      t.references :location, index: true, foreign_key: true
      t.references :classroom, index: true, foreign_key: true
      t.text :days, array: true, default: []
      t.time :start_time
      t.time :end_time
      t.string :block

      t.timestamps null: false
    end
  end
end
