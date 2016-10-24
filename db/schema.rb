# encoding: UTF-8
# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended that you check this file into your version control system.

ActiveRecord::Schema.define(version: 20161024043305) do

  # These are extensions that must be enabled in order to support this database
  enable_extension "plpgsql"

  create_table "classrooms", force: :cascade do |t|
    t.string   "room_number"
    t.integer  "capacity"
    t.integer  "location_id"
    t.datetime "created_at",  null: false
    t.datetime "updated_at",  null: false
  end

  add_index "classrooms", ["location_id"], name: "index_classrooms_on_location_id", using: :btree

  create_table "courses", force: :cascade do |t|
    t.string   "code"
    t.string   "name"
    t.integer  "program_id"
    t.integer  "credit_hours"
    t.datetime "created_at",   null: false
    t.datetime "updated_at",   null: false
  end

  add_index "courses", ["program_id"], name: "index_courses_on_program_id", using: :btree

  create_table "locations", force: :cascade do |t|
    t.string   "campus"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "professors", force: :cascade do |t|
    t.string   "first_name"
    t.string   "last_name"
    t.string   "email"
    t.integer  "program_id"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  add_index "professors", ["program_id"], name: "index_professors_on_program_id", using: :btree

  create_table "programs", force: :cascade do |t|
    t.string   "name"
    t.datetime "created_at", null: false
    t.datetime "updated_at", null: false
  end

  create_table "scheduled_courses", force: :cascade do |t|
    t.integer  "semester_id"
    t.integer  "course_id"
    t.integer  "professor_id"
    t.integer  "location_id"
    t.integer  "classroom_id"
    t.text     "days",         default: [],              array: true
    t.time     "start_time"
    t.time     "end_time"
    t.string   "block"
    t.datetime "created_at",                null: false
    t.datetime "updated_at",                null: false
  end

  add_index "scheduled_courses", ["classroom_id"], name: "index_scheduled_courses_on_classroom_id", using: :btree
  add_index "scheduled_courses", ["course_id"], name: "index_scheduled_courses_on_course_id", using: :btree
  add_index "scheduled_courses", ["location_id"], name: "index_scheduled_courses_on_location_id", using: :btree
  add_index "scheduled_courses", ["professor_id"], name: "index_scheduled_courses_on_professor_id", using: :btree
  add_index "scheduled_courses", ["semester_id"], name: "index_scheduled_courses_on_semester_id", using: :btree

  create_table "semesters", force: :cascade do |t|
    t.string   "year"
    t.string   "season"
    t.datetime "created_at",              null: false
    t.datetime "updated_at",              null: false
    t.date     "start_date"
    t.date     "end_date"
    t.date     "first_block_start_date"
    t.date     "first_block_end_date"
    t.date     "second_block_start_date"
    t.date     "second_block_end_date"
  end

  add_foreign_key "classrooms", "locations"
  add_foreign_key "courses", "programs"
  add_foreign_key "professors", "programs"
  add_foreign_key "scheduled_courses", "classrooms"
  add_foreign_key "scheduled_courses", "courses"
  add_foreign_key "scheduled_courses", "locations"
  add_foreign_key "scheduled_courses", "professors"
  add_foreign_key "scheduled_courses", "semesters"
end
