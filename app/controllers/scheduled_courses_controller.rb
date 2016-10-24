class ScheduledCoursesController < ApplicationController
  before_action :set_scheduled_course, only: [:show, :edit, :update, :destroy]

  def index
    @scheduled_courses = ScheduledCourse.all
  end

  def show
  end

  def new
    @scheduled_course = ScheduledCourse.new
  end

  def edit
  end

  def create
    @scheduled_course = ScheduledCourse.new(scheduled_course_params)
    @scheduled_course.days = params[:scheduled_course][:days].reject{ |day| day.blank? }
    
    respond_to do |format|
      if @scheduled_course.save
        format.html { redirect_to @scheduled_course, notice: 'Scheduled course was successfully created.' }
        format.json { render :show, status: :created, location: @scheduled_course }
      else
        format.html { render :new }
        format.json { render json: @scheduled_course.errors, status: :unprocessable_entity }
      end
    end
  end

  def update
    respond_to do |format|
      if @scheduled_course.update(scheduled_course_params)
        format.html { redirect_to @scheduled_course, notice: 'Scheduled course was successfully updated.' }
        format.json { render :show, status: :ok, location: @scheduled_course }
      else
        format.html { render :edit }
        format.json { render json: @scheduled_course.errors, status: :unprocessable_entity }
      end
    end
  end

  def destroy
    @scheduled_course.destroy
    respond_to do |format|
      format.html { redirect_to scheduled_courses_url, notice: 'Scheduled course was successfully destroyed.' }
      format.json { head :no_content }
    end
  end

  private
    def set_scheduled_course
      @scheduled_course = ScheduledCourse.find(params[:id])
    end

    def scheduled_course_params
      params.require(:scheduled_course).permit(:semester_id, :course_id, :professor_id, :location_id, :classroom_id, :days, :start_time, :end_time, :block)
    end
end
