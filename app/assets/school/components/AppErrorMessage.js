import React from 'react'
import PropTypes from "prop-types";

const AppErrorMessage = ({error}) => {
  return (
    <>
      {
        (error !== null && error !== undefined) ? (
          <div className="alert alert-danger">
            {error}
          </div>
        ) : ''
      }
    </>
  )
}
AppErrorMessage.propTypes = {
  error: PropTypes.string
}
export default React.memo(AppErrorMessage)
