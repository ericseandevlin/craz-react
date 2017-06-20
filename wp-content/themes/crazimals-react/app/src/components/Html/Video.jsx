import React, {Component} from 'react';

import JWPlayer from './JWPlayer.jsx';
import VideoJSPlayer from './VideoJSPlayer.jsx';

export default class Video extends React.Component {
  render() {

    if ('videojs' === this.props.player) {
      return (
        <VideoJSPlayer {...this.props.options}/>
      )
    }
    let options = {
      playerId: '',
      playerScript: ''
    };
    return (
      <JWPlayer {...options} />
    );
  }
}

