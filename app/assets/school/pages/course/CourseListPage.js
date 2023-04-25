import React from 'react'
import CourseList from "./../../views/course/CourseList";
import LoadablePage from "../LoadablePage";

const CourseListPage = () => {

  return <LoadablePage
    Component={CourseList}
    url={window.abeApp.urls.api_school_course_list}/>
}

export default CourseListPage
