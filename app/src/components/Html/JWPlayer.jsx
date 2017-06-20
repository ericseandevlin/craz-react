import React, {Component} from 'react';
import ReactJWPlayer from 'react-jw-player';

export default class JWPlayer extends React.Component {
  render() {
    return (
      <ReactJWPlayer {...this.props}/>
    );
  }
}

