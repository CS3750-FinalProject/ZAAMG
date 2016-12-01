#LOAD DATA LOCAL INFILE '\/home\/gc67781\/public_html\/zaamg\/data\/semester_data.txt'
#INTO TABLE Semester
#FIELDS TERMINATED BY '|';

#LOAD DATA LOCAL INFILE '\/home\/gc67781\/public_html\/zaamg\/data\/course_data.txt'
#INTO TABLE Course
#FIELDS TERMINATED BY '|';

#LOAD DATA LOCAL INFILE '\/home\/gc67781\/public_html\/zaamg\/data\/professor_data.txt'
#INTO TABLE Professor
#FIELDS TERMINATED BY '|';

LOAD DATA LOCAL INFILE '\/home\/gc67781\/public_html\/zaamg\/data\/classroom_data.txt'
INTO TABLE Classroom
FIELDS TERMINATED BY '|';


#  Uncomment whatever you need as you need it
#  Modify file paths also as needed