import React from 'react'
import Loadable from "../../Loadable";
import CampusList from "./CampusList";

const CampusListPage = () => {

  return <Loadable
    component={CampusList}
    url={window.abeApp.urls.api_school_campus_list}/>
}

export default CampusListPage
