import React from 'react'
import Loadable from "../Loadable";
import CourseList from "./../../views/course/CourseList";

const CourseListPage = () => {

  return <Loadable
    Component={CourseList}
    url={window.abeApp.urls.api_school_course_list}/>
}

export default CourseListPage
