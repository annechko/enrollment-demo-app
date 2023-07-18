import React from 'react'
import Loadable from "../../../../App/Helper/Loadable";
import CourseList from "./CourseList";

const CourseListPage = () => {

  return <Loadable
    component={CourseList}
    url={window.abeApp.urls.api_school_course_list}/>
}

export default CourseListPage
