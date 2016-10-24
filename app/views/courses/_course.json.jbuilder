json.extract! course, :id, :code, :name, :program_id, :credit_hours, :created_at, :updated_at
json.url course_url(course, format: :json)