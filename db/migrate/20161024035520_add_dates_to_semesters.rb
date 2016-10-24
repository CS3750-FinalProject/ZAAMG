class AddDatesToSemesters < ActiveRecord::Migration
  def change
    add_column :semesters, :start_date, :date
    add_column :semesters, :end_date, :date
    add_column :semesters, :first_block_start_date, :date
    add_column :semesters, :first_block_end_date, :date
    add_column :semesters, :second_block_start_date, :date
    add_column :semesters, :second_block_end_date, :date
  end
end
