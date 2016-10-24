class ScheduledCourse < ActiveRecord::Base
  belongs_to :semester
  belongs_to :course
  belongs_to :professor
  belongs_to :location
  belongs_to :classroom
end
