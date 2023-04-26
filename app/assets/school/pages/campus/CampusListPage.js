import React from 'react'
import Loadable from "../Loadable";
import CampusList from "./../../views/campus/CampusList";

const CampusListPage = () => {

  return <Loadable
    Component={CampusList}
    url={window.abeApp.urls.api_school_campus_list}/>
}

export default CampusListPage
