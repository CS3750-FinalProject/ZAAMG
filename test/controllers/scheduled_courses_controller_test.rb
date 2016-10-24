require 'test_helper'

class ScheduledCoursesControllerTest < ActionController::TestCase
  setup do
    @scheduled_course = scheduled_courses(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:scheduled_courses)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create scheduled_course" do
    assert_difference('ScheduledCourse.count') do
      post :create, scheduled_course: { block: @scheduled_course.block, location_id: @scheduled_course.location_id, classroom_id: @scheduled_course.classroom_id, course_id: @scheduled_course.course_id, days: @scheduled_course.days, end_time: @scheduled_course.end_time, professor_id: @scheduled_course.professor_id, semester_id: @scheduled_course.semester_id, start_time: @scheduled_course.start_time }
    end

    assert_redirected_to scheduled_course_path(assigns(:scheduled_course))
  end

  test "should show scheduled_course" do
    get :show, id: @scheduled_course
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @scheduled_course
    assert_response :success
  end

  test "should update scheduled_course" do
    patch :update, id: @scheduled_course, scheduled_course: { block: @scheduled_course.block, location_id: @scheduled_course.location_id, classroom_id: @scheduled_course.classroom_id, course_id: @scheduled_course.course_id, days: @scheduled_course.days, end_time: @scheduled_course.end_time, professor_id: @scheduled_course.professor_id, semester_id: @scheduled_course.semester_id, start_time: @scheduled_course.start_time }
    assert_redirected_to scheduled_course_path(assigns(:scheduled_course))
  end

  test "should destroy scheduled_course" do
    assert_difference('ScheduledCourse.count', -1) do
      delete :destroy, id: @scheduled_course
    end

    assert_redirected_to scheduled_courses_path
  end
end
